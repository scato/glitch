noop := () => {
};

rem := noop;
rem ! "we can now use this noop action to place remarks";

rem ! "every time input is fired, fire output with each of its parts";
explode := (input, separator, output) => {
    loop := str => {
        rem ! "find the first occurence of the separator";
        pos := strpos(str, separator);
        len := strlen(str);
        found := pos !== "false";

        rem ! "if found, split the string, otherwise take the entire string";
        first := found ? substr(str, "0", pos) : str;
        rest := found ? pos < len - "1" ? substr(str, pos + "1") : "" : "";
        
        rem ! "fire output with the first part";
        output ! first;

        rem ! "if found, continue with the rest, otherwise fire noop with an empty string";
        found ? loop : noop ! rest;
    };

    input += loop;
};

rem ! "connect two events, mapping the values between them";
map := (in, func, out) => {
    in += value => {
        out ! func(value);
    };
};

rem ! "connect two events, filtering the values between them";
filter := (in, func, out) => {
    in += value => {
        func(value) ? out : noop ! value;
    };
};

rem ! "read a file and fire an action for each line";
file := (filename, lines) => {
    * contents, trimmed;

    map ! (contents, trim, trimmed);
    explode ! (trimmed, "\n", lines);
    file_get_contents ! (filename, contents);
};

rem ! "connect two events, taking only the first so many values";
take := (in, num, out) => {
    next := value => {
        in -= next;
        out ! value;
        take ! (in, num - "1", out);
    };
    in += num > "0" ? next : noop;
};

rem ! "connect two events, skipping the first so many values";
skip := (in, num, out) => {
    next := value => {
        in -= next;
        skip ! (in, num - "1", out);
    };
    in += num > "0" ? next : out;
};

rem ! "sort the values of an event and record them in a buffer";
sort := (in, func, out) => {
    * pivot, rest;

    take ! (in, "1", pivot);
    skip ! (in, "1", rest);

    pivot += pivot_value => {
        * left, right, left_buffer, right_buffer;

        filter ! (rest, x -> func(x) < func(pivot_value), left);
        filter ! (rest, x -> func(x) >= func(pivot_value), right);

        sort ! (left, func, left_buffer);
        sort ! (right, func, right_buffer);

        out += fireback => {
            left_buffer ! fireback;
            fireback ! pivot_value;
            right_buffer ! fireback;
        };
    };
};

id := x -> x;

shuffle := (in, out) => {
    microtime ! seed => {
        randomize := string -> md5(seed . string);

        sort ! (in, randomize, out);
    };
};


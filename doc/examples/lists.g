noop := () => {
};

map := (a, f, b) => {
    a += x => {
        b ! f(x);
    };
};

filter := (a, f, b) => {
    a += x => {
        f(x) ? b : noop ! x;
    };
};

reduce := (a, f, x, b) => {
    next ! (a, y => {
        z := f(x, y);

        b ! z;
        reduce ! (a, f, z, b);
    });
};

take := (a, n, b) => {
    next ! (a, x => {
        n > "0" ? b : noop ! x;

        take ! (a, n - "1", b);
    });
};

skip := (a, n, b) => {
    next ! (a, x => {
        n > "0" ? noop : b ! x;

        skip ! (a, n - "1", b);
    });
};


include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"2b\"";

    * a, b, c;
    eachr ! (a, b, c);
    c += (x, y) => {
        println ! x . y;
    };

    a ! "0";
    a ! "1";
    b ! "a";
    b ! "b";
    a ! "2";
    b ! "c";
    b ! "d";
};


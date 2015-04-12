include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"1a\", \"1b\", \"2b\", \"2c\", \"2d\"";

    * a, b, c;
    inner ! (a, b, c);
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


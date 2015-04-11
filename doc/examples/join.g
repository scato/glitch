include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"0a\", \"1a\", \"0b\", \"1b\", \"0c\", \"1c\", \"2c\", \"0d\", \"1d\", \"2d\"";

    * a, b, c;
    join ! (a, b, c);
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


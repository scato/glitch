include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"b\"";

    * a, b, c;
    sample ! (a, b, c);
    c += println;

    a ! "0";
    a ! "1";
    b ! "a";
    b ! "b";
    a ! "2";
    b ! "c";
    b ! "d";
};


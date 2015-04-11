include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"0\", \"1\", \"a\", \"b\", \"2\", \"c\", \"d\"";

    * a, b, c;
    merge ! (a, b, c);
    c += println;

    a ! "0";
    a ! "1";
    b ! "a";
    b ! "b";
    a ! "2";
    b ! "c";
    b ! "d";
};


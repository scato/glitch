include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"1\", \"1\", \"2\", \"2\"";

    * a, b;
    last ! (a, b);

    a ! "0";
    a ! "1";
    b ! println;
    b ! println;
    a ! "2";
    b ! println;
    b ! println;
};


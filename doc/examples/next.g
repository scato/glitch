include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"1\"";

    * a, b;

    next ! (a, b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
};


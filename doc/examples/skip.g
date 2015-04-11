include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"3\", \"4\"";

    * a, b;
    skip ! (a, "2", b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


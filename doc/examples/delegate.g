include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"1\", \"2\", \"3\", \"4\"";

    * a, b;
    delegate ! (a, b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


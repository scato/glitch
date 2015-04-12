include ! "doc/examples/events.g";
include ! "doc/examples/lists.g";

main += args => {
    println ! "Should have output: \"1\", \"2\"";

    * a, b;
    take ! (a, "2", b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


include ! "doc/examples/events.g";
include ! "doc/examples/lists.g";

main += args => {
    println ! "Should have output: \"2\", \"3\", \"4\", \"5\"";

    * a, b;
    map ! (a, x -> x + "1", b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


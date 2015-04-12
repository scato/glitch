include ! "doc/examples/events.g";
include ! "doc/examples/lists.g";

main += args => {
    println ! "Should have output: \"1\", \"3\", \"6\", \"10\"";

    * a, b;
    reduce ! (a, (x, y) -> x + y, "0", b);
    b += println;

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


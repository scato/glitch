include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"2\", \"3\"";

    * a, b, c, d;
    during ! (a, b, c, d);
    d += println;

    a ! "1";
    b ! ();
    a ! "2";
    a ! "3";
    c ! ();
    a ! "4";
};


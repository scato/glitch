include ! "doc/examples/events.g";
include ! "doc/examples/phases.g";

main += args => {
    println ! "Should have output: \"1\", \"4\"";

    * a, b, c, d;
    between ! (a, b, c, d);
    d += println;

    a ! "1";
    b ! ();
    a ! "2";
    a ! "3";
    c ! ();
    a ! "4";
};


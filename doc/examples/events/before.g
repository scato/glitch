include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"1\", \"2\"";

    * a, b, c;
    before ! (a, b, c);
    c += println;

    a ! "1";
    a ! "2";
    b ! ();
    a ! "3";
    a ! "4";
};

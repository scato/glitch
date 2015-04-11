include ! "doc/examples/events.g";

main += args => {
    println ! "Should have output: \"12\", \"23\", \"34\"";

    * a, b;
    pair ! (a, b);
    b += (x, y) => {
        println ! x . y;
    };

    a ! "1";
    a ! "2";
    a ! "3";
    a ! "4";
};


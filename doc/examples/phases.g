during := (a, b, c, d) => {
    next0 ! (b, () => {
        a += d;

        next0 ! (c, () => {
            a -= d;

            during ! (a, b, c, d);
        });
    });
};

between := (a, b, c, d) => {
    a += d;

    next0 ! (b, () => {
        a -= d;

        next0 ! (c, () => {
            between ! (a, b, c, d);
        });
    });
};


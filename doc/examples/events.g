delegate := (a, b) => {
    a += b;
};

merge := (a, b, c) => {
    a += c;
    b += c;
};

join := (a, b, c) => {
    a += x => {
        b += y => {
            c ! (x, y);
        };
    };
};

joinr := (a, b, c) => {
    b += y => {
        a += x => {
            c ! (x, y);
        };
    };
};

outer := (a, b, c) => {
    join ! (a, b, c);
    joinr ! (a, b, c);
};

next := (a, b) => {
    f := x => {
        a -= f;
        b ! x;
    };

    a += f;
};

next0 := (a, b) => {
    f := () => {
        a -= f;
        b ! ();
    };

    a += f;
};

before := (a, b, c) => {
    a += c;

    next0 ! (b, () => {
        a -= c;
    });
};

after := (a, b, c) => {
    next0 ! (b, () => {
        a += c;
    });
};

each := (a, b, c) => {
    a += x => {
        before ! (b, a, y => {
            c ! (x, y);
        });
    };
};

eachr := (a, b, c) => {
    b += y => {
        before ! (a, b, x => {
            c ! (x, y);
        });
    };
};

inner := (a, b, c) => {
    each ! (a, b, c);
    eachr ! (a, b, c);
};

last := (a, b) => {
    each ! (a, b, (x, y) => {
        y ! x;
    });
};

noop := () => {
};

take := (a, n, b) => {
    next ! (a, x => {
        n > "0" ? b : noop ! x;
        take ! (a, n - "1", b);
    });
};

skip := (a, n, b) => {
    next ! (a, x => {
        n > "0" ? noop : b ! x;
        skip ! (a, n - "1", b);
    });
};

pair := (a, b) => {
    a += x => {
        next ! (a, y => {
            b ! (x, y);
        });
    };
};

sample := (a, b, c) => {
    eachr ! (a, b, (x, y) => {
        c ! y;
    });
};

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


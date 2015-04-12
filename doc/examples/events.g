delegate := (a, b) => {
    a += b;
};

merge := (a, b, c) => {
    delegate ! (a, c);
    delegate ! (b, c);
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

sample := (a, b, c) => {
    eachr ! (a, b, (x, y) => {
        c ! y;
    });
};

pair := (a, b) => {
    a += x => {
        next ! (a, y => {
            b ! (x, y);
        });
    };
};


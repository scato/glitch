include ! "lib/stdlib.g";

main += args => {
    * lines, sorted;
    sort ! (lines, id, sorted);
    file ! (args, lines);
    sorted ! println;
};


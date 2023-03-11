export function formatNumber(number) {
    let v = Number(number);
    if (isNaN(v)) {
        v = 0.00;
    }
    return v.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

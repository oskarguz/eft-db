export function openNav() {
    document.getElementById('mainSideNav').style.width = '250px';
    document.getElementById('main').style.opacity = '70%';
}

export function closeNav() {
    document.getElementById('mainSideNav').style.width = '0';
    document.getElementById('main').style.opacity = null;
}

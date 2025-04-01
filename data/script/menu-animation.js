//Loop for each menu
for (let i = 1; i < 4; i++) {
    // Get the menu and the sub-menu which is the child of the menu
    const subMenu = document.querySelector('.menu:nth-of-type(' + i + ') .sub-menu');
    const menu = document.querySelector('.menu:nth-child(' + i + ')');

    // Add event listener to the menu for mouse leave
    menu.addEventListener('mouseleave', function() {
        // Add class to the sub-menu to animate it before it was closed
        subMenu.classList.add('sub-menu-end');
        // Remove the class to the sub-menu to leave the animation play
        subMenu.classList.remove('sub-menu');
    });

    // Add event listener to the sub-menu for animation end
    subMenu.addEventListener('animationend', function() {
        // Add class to the sub-menu to hide it after the animation was ended
        subMenu.classList.add('sub-menu');
        // Remove the class to the sub-menu to hide it after the animation was ended
        subMenu.classList.remove('sub-menu-end');
    });
}
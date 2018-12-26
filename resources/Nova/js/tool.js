Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'menu-builder-dashboard',
            path: '/menu-builder/dashboard',
            component: require('./components/dashboard'),
        },
        {
            name: 'menu-builder-edit-menu',
            path: '/menu-builder/edit',
            component: require('./components/editMenu'),
        },
    ])
})

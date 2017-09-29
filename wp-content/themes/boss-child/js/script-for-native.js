var items = [{
            label: 'Home', // label for menu 
            url: '/'       // link for menu
        },
        {
            label: 'Search Members',
            url: '/members'
        }, // This for menu without sub menu
				//...........
        {
            "label": "Parent Item", // this case for menu with submenu
            "grouping": "[grouping]",
            "isGrouping": true,
            "isSubmenu": false,
            "subLinks": [{  // submenu item
                    "url": "https://www.yoursite.com/abc",
                    "label": "Child ABC",
                    "subLinks": []
                },
                { //submenu item
                    "url": "https://www.yoursite.com/def",
                    "label": "Child DEF",
                    "subLinks": []
                }
            ]
        }
    ];

    var json = JSON.stringify(items);

    window.href = 'gonative://sidebar/setItems?items=' + encodeURIComponent(json);
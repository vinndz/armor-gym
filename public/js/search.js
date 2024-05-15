function search(searchFieldName, tableContainerName, route) {
    const searchField = document.getElementById(searchFieldName);
    const tableContainer = document.getElementById(tableContainerName);
    let temp;

    searchField.addEventListener("input", () => {
        const { pathname } = window.location;

        window.history.pushState(
            {},
            null,
            `${pathname}?keyword=${searchField.value}`
        );

        if (!searchField.value) {
            window.history.pushState({}, null, `${pathname}`);
        }

        clearTimeout(temp);
        temp = setTimeout(() => {
            const url = route;

            fetch(`${url}keyword=${searchField.value}`)
                .then((response) => {
                    return response.text();
                })
                .then((response) => {
                    tableContainer.innerHTML = `${response}`;
                });
        }, 250);
    });
}

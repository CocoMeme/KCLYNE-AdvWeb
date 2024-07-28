(function() {
    // Initialize Algolia client
    var client = algoliasearch('32IRPNW53S', '54c1b0ef3dc6115cd239c57e2474f4a7');
    var serviceIndex = client.initIndex('services');
    var productIndex = client.initIndex('products');
    var enterPressed = false;
    var baseUrl = window.location.origin + '/';

    // Initialize the autocomplete functionality
    autocomplete('#aa-search-input', { hint: false }, [
        {
            source: autocomplete.sources.hits(serviceIndex, { hitsPerPage: 5 }),
            displayKey: 'service_name',
            templates: {
                header: '<div class="algolia-result">Services</div>',
                suggestion: function(suggestion) {
                    console.log('Service suggestion:', suggestion); // Debugging: Log the service suggestion object
                    const price = typeof suggestion.price === 'number' ? suggestion.price: 'N/A';
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                <img src="${baseUrl}images/services/${suggestion.service_image}" alt="img" class="algolia-thumb">
                                ${suggestion._highlightResult.service_name.value}
                            </span>
                        </div>
                        <div class="algolia-details">
                            <span>₱${price}</span>
                        </div>
                        <div class="algolia-details">
                            <span>${suggestion._highlightResult.description.value}</span>
                        </div>
                    `;
                    return markup;
                },
                empty: function(result) {
                    console.log('No service results:', result); // Debugging: Log the no service results
                    return 'Sorry, we did not find any results for "' + result.query + '"';
                }
            }
        },
        {
            source: autocomplete.sources.hits(productIndex, { hitsPerPage: 5 }),
            displayKey: 'name',
            templates: {
                header: '<div class="algolia-result">Products</div>',
                suggestion: function(suggestion) {
                    console.log('Product suggestion:', suggestion); // Debugging: Log the product suggestion object
                    const firstImagePath = suggestion.image_path.split(',')[0]; // Get the first image path
                    const price = typeof suggestion.price === 'string' ? suggestion.price : 'N/A';
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                <img src="${baseUrl}images/products/${firstImagePath}" alt="img" class="algolia-thumb">
                                ${suggestion._highlightResult.name.value}
                            </span>
                        </div>
                        <div class="algolia-details">
                            <span>₱${price}</span>
                        </div>
                        <div class="algolia-details">
                            <span>${suggestion._highlightResult.description.value}</span>
                        </div>
                        <div class="algolia-details">
                            <span>Stock: ${suggestion.stock_quantity}</span>
                        </div>
                    `;
                    return markup;
                },
                empty: function(result) {
                    console.log('No product results:', result); // Debugging: Log the no product results
                    return 'Sorry, we did not find any results for "' + result.query + '"';
                }
            }
        }
    ]).on('autocomplete:selected', function(event, suggestion, dataset) {
        console.log('Selected suggestion:', suggestion, 'Dataset:', dataset); // Debugging: Log the selected suggestion and dataset
        if (dataset === 0) { // Service selected
            const serviceName = suggestion._highlightResult?.service_name?.value || suggestion.service_name;
            saveSearchHistory(serviceName, suggestion.objectID, suggestion.service_image, 'service_view');
            window.location.href = window.location.origin + '/service/' + suggestion.objectID;
        } else if (dataset === 1) { // Product selected
            const productName = suggestion._highlightResult?.name?.value || suggestion.name;
            saveSearchHistory(productName, suggestion.objectID, suggestion.image_path, 'shop');
            window.location.href = window.location.origin + '/service_view/' + suggestion.objectID;
        }
        enterPressed = true;
    }).on('keyup', function(event) {
        console.log('Keyup event:', event); // Debugging: Log the keyup event
        if (event.keyCode == 13 && !enterPressed) {
            const query = document.getElementById('aa-search-input').value;
            console.log('Enter key pressed, query:', query); // Debugging: Log the query on Enter key press
            saveSearchHistory(query);
            window.location.href = window.location.origin + '/search-algolia?q=' + query;
        }
    });

    // Save search query to local storage
    function saveSearchHistory(name, objectID, imagePath, type) {
        console.log('Saving search history:', name, objectID, imagePath, type); // Debugging: Log the search history being saved
        let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        if (!searchHistory.some(item => item.name === name)) {
            searchHistory.unshift({ name, objectID, imagePath, type });
            if (searchHistory.length > 5) {
                searchHistory.pop();
            }
            localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
            console.log('Search history saved:', searchHistory); // Debugging: Log the saved search history
        }
    }

    // Display search history when the input is focused
    document.getElementById('aa-search-input').addEventListener('focus', function() {
        console.log('Input focused'); // Debugging: Log when the input is focused
        const searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        const dropdown = document.createElement('div');
        dropdown.className = 'search-history-dropdown';

        dropdown.innerHTML = searchHistory.map(item => {
            const imgSrc = item.imagePath ? `${baseUrl}images/${item.type === 'service' ? 'services' : 'products'}/${item.imagePath}` : `${baseUrl}images/default-placeholder.png`;

            return `
                <div class="search-history-item" data-id="${item.objectID}" data-type="${item.type}" style="cursor: pointer;">
                    <img src="${imgSrc}" alt="img" class="algolia-thumb">
                    ${item.name}
                </div>
            `;
        }).join('');

        this.parentNode.appendChild(dropdown);
        console.log('Dropdown appended:', dropdown); // Debugging: Log the dropdown appended

        document.querySelectorAll('.search-history-item').forEach(item => {
            item.addEventListener('click', function() {
                const objectId = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                console.log('Search history item clicked:', objectId, type); // Debugging: Log the clicked search history item
                if (objectId && type) {
                    window.location.href = window.location.origin + `/${type}/` + objectId;
                } else {
                    console.error('ID or type not found');
                }
            });
        });
    });

    // Trigger search on search history item click
    function triggerSearch(query) {
        console.log('Triggering search for query:', query); // Debugging: Log the query triggering the search
        document.getElementById('aa-search-input').value = query;
        const event = new KeyboardEvent('keyup', { keyCode: 13 });
        document.getElementById('aa-search-input').dispatchEvent(event);
    }

    document.getElementById('aa-search-input').addEventListener('blur', function() {
        setTimeout(() => {
            const dropdown = document.querySelector('.search-history-dropdown');
            if (dropdown) {
                dropdown.remove();
                console.log('Dropdown removed'); // Debugging: Log the dropdown removed
            }
        }, 200); 
    });

    function clearSearchInputIfOnIndexPage() {
        const searchInput = document.getElementById('aa-search-input');
        if (window.location.pathname === '/' || window.location.pathname === '/index') {
            searchInput.value = '';
            localStorage.removeItem('searchHistory');
            console.log('Cleared search input and history on index page'); // Debugging: Log clearing search input and history on index page
        }
    }

    window.addEventListener('popstate', clearSearchInputIfOnIndexPage);
    document.addEventListener('DOMContentLoaded', clearSearchInputIfOnIndexPage);
})();

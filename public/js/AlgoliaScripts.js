(function() {
    var client = algoliasearch('32IRPNW53S', '54c1b0ef3dc6115cd239c57e2474f4a7');
    var serviceIndex = client.initIndex('services');
    var productIndex = client.initIndex('products');
    var enterPressed = false;
    var baseUrl = window.location.origin + '/';

    autocomplete('#aa-search-input', { hint: false }, [
        {
            source: autocomplete.sources.hits(serviceIndex, { hitsPerPage: 5 }),
            displayKey: 'service_name',
            templates: {
                header: '<div class="algolia-result">Services</div>',
                suggestion: function(suggestion) {
                    console.log('Service suggestion:', suggestion);
                    const price = typeof suggestion.price === 'number' ? suggestion.price : 'N/A';
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
                    `;
                    return markup;
                },
                empty: function(result) {
                    console.log('No service results:', result);
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
                    console.log('Product suggestion:', suggestion);
                    const firstImagePath = suggestion.image_path.split(',')[0];
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
                    `;
                    return markup;
                },
                empty: function(result) {
                    console.log('No product results:', result);
                    return 'Sorry, we did not find any results for "' + result.query + '"';
                }
            }
        }
    ]).on('autocomplete:selected', function(event, suggestion, dataset) {
        console.log('Selected suggestion:', suggestion, 'Dataset:', dataset);
        if (dataset === 1) {
            const serviceName = suggestion._highlightResult?.service_name?.value || suggestion.service_name;
            saveSearchHistory(serviceName, suggestion.objectID, suggestion.service_image, 'services');
            window.location.href = `${window.location.origin}/service_view/${suggestion.objectID}`;
        } else if (dataset === 2) {
            const productName = suggestion._highlightResult?.name?.value || suggestion.name;
            const productImagePath = suggestion.image_path.split(',')[0];
            saveSearchHistory(productName, suggestion.objectID, productImagePath, 'products');
            window.location.href = `${window.location.origin}/product_view/${suggestion.objectID}`;
        }
        enterPressed = true;
    }).on('keyup', function(event) {
        console.log('Keyup event:', event);
        if (event.keyCode == 13 && !enterPressed) {
            const query = document.getElementById('aa-search-input').value;
            console.log('Enter key pressed, query:', query);
            saveSearchHistory(query);
            window.location.href = window.location.origin + '/search-algolia?q=' + query;
        }
    });

    function saveSearchHistory(name, objectID, imagePath, type) {
        console.log('Saving search history:', name, objectID, imagePath, type);
        let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        if (!searchHistory.some(item => item.name === name)) {
            searchHistory.unshift({ name, objectID, imagePath, type });
            if (searchHistory.length > 5) {
                searchHistory.pop();
            }
            localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
            console.log('Search history saved:', searchHistory);
        }
    }

    document.getElementById('aa-search-input').addEventListener('focus', function() {
        console.log('Input focused');
        const searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        const dropdown = document.createElement('div');
        dropdown.className = 'search-history-dropdown';

        dropdown.innerHTML = searchHistory.map(item => {
            const imgSrc = item.imagePath ? `${baseUrl}images/${item.type === 'services' ? 'services' : 'products'}/${item.imagePath}` : `${baseUrl}images/default-placeholder.png`;

            return `
                <div class="search-history-item" data-id="${item.objectID}" data-type="${item.type}" style="cursor: pointer;">
                    <img src="${imgSrc}" alt="img" class="algolia-thumb">
                    ${item.name}
                </div>
            `;
        }).join('');

        this.parentNode.appendChild(dropdown);
        console.log('Dropdown appended:', dropdown);

        document.querySelectorAll('.search-history-item').forEach(item => {
            item.addEventListener('click', function() {
                const objectId = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                console.log('Search history item clicked:', objectId, type);
                if (objectId && type) {
                    window.location.href = window.location.origin + `/${type}_view/` + objectId;
                } else {
                    console.error('ID or type not found');
                }
            });
        });
    });

    function triggerSearch(query) {
        console.log('Triggering search for query:', query);
        document.getElementById('aa-search-input').value = query;
        const event = new KeyboardEvent('keyup', { keyCode: 13 });
        document.getElementById('aa-search-input').dispatchEvent(event);
    }

    document.getElementById('aa-search-input').addEventListener('blur', function() {
        setTimeout(() => {
            const dropdown = document.querySelector('.search-history-dropdown');
            if (dropdown) {
                dropdown.remove();
                console.log('Dropdown removed');
            }
        }, 200); 
    });

    function clearSearchInputIfOnIndexPage() {
        const searchInput = document.getElementById('aa-search-input');
        if (window.location.pathname === '/' || window.location.pathname === '/index') {
            searchInput.value = '';
            localStorage.removeItem('searchHistory');
            console.log('Cleared search input and history on index page');
        }
    }

    window.addEventListener('popstate', clearSearchInputIfOnIndexPage);
    document.addEventListener('DOMContentLoaded', clearSearchInputIfOnIndexPage);
})();

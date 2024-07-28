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
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                <img src="${baseUrl}images/services/${suggestion.service_image}" alt="img" class="algolia-thumb">
                                ${suggestion._highlightResult.service_name.value}
                            </span>
                        </div>
                        <div class="algolia-details">
                            <span>₱${(suggestion.price).toFixed(2)}</span>
                        </div>
                        <div class="algolia-details">
                            <span>${suggestion._highlightResult.description.value}</span>
                        </div>
                    `;
                    return markup;
                },
                empty: function(result) {
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
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                <img src="${baseUrl}images/products/${suggestion.image_path}" alt="img" class="algolia-thumb">
                                ${suggestion._highlightResult.name.value}
                            </span>
                        </div>
                        <div class="algolia-details">
                            <span>₱${(suggestion.price).toFixed(2)}</span>
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
                    return 'Sorry, we did not find any results for "' + result.query + '"';
                }
            }
        }
    ]).on('autocomplete:selected', function(event, suggestion, dataset) {
        if (dataset === 0) { // Service selected
            saveSearchHistory(suggestion._highlightResult.service_name.value, suggestion.objectID, suggestion.service_image, 'service');
            window.location.href = window.location.origin + '/service/' + suggestion.objectID;
        } else if (dataset === 1) { // Product selected
            saveSearchHistory(suggestion._highlightResult.name.value, suggestion.objectID, suggestion.image_path, 'shop');
            window.location.href = window.location.origin + '/shop/' + suggestion.objectID;
        }
        enterPressed = true;
    }).on('keyup', function(event) {
        if (event.keyCode == 13 && !enterPressed) {
            const query = document.getElementById('aa-search-input').value;
            saveSearchHistory(query);
            window.location.href = window.location.origin + '/search-algolia?q=' + query;
        }
    });
    
    // Save search query to local storage
    function saveSearchHistory(name, objectID, imagePath, type) {
        let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
        if (!searchHistory.some(item => item.name === name)) {
            searchHistory.unshift({ name, objectID, imagePath, type });
            if (searchHistory.length > 5) {
                searchHistory.pop();
            }
            localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
            console.log('Search history saved:', searchHistory); // Debugging
        }
    }
    

    // Display search history when the input is focused
    document.getElementById('aa-search-input').addEventListener('focus', function() {
        console.log('Input focused'); // Debugging
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
        console.log('Dropdown appended:', dropdown); // Debugging
    
        document.querySelectorAll('.search-history-item').forEach(item => {
            item.addEventListener('click', function() {
                const objectId = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
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
        document.getElementById('aa-search-input').value = query;
        const event = new KeyboardEvent('keyup', { keyCode: 13 });
        document.getElementById('aa-search-input').dispatchEvent(event);
    }
    
    document.getElementById('aa-search-input').addEventListener('blur', function() {
        setTimeout(() => {
            const dropdown = document.querySelector('.search-history-dropdown');
            if (dropdown) {
                dropdown.remove();
                console.log('Dropdown removed'); // Debugging
            }
        }, 200); 
    }); 
    function clearSearchInputIfOnIndexPage() {
        const searchInput = document.getElementById('aa-search-input');
        if (window.location.pathname === '/' || window.location.pathname === '/index') {
            searchInput.value = '';
            localStorage.removeItem('searchHistory');
        }
    }
    
    window.addEventListener('popstate', clearSearchInputIfOnIndexPage);
    document.addEventListener('DOMContentLoaded', clearSearchInputIfOnIndexPage);
    
})();

$.getJSON(baseURL+'/getcities.php', function(data) {
    localStorage.setItem('cities', JSON.stringify(data));
});

$.getJSON(baseURL+'/getcategories.php', function(data) {
   localStorage.setItem('categories', JSON.stringify(data));
});
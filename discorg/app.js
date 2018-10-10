angular

.module('smartFridge', [])

.controller('mainCtrl', function ($scope, $filter) {
    $scope.fridgeItems = [];
    $scope.item = { };
    $scope.orderByField = 'type';
    $scope.reverseSort = false;   

    // Reset everything
    $scope.resetFridge = function () {
       $scope.fridgeItems = []; 
       $scope.resetForm();
       generateFridge();
    }

    // Reset the form
    $scope.resetForm = function () {
      $scope.item = { };
      $scope.fridgeForm.$setPristine();
    }

    // Fill the form with data from clicked table row
    $scope.fillForm = function (i) {
        $scope.item.bName = i.brand;
        $scope.item.name = i.type;
        $scope.item.quantity = i.qty;
    }

    // Add item to the fridge
    $scope.addItem = function(item) {
        var i = {
            brand:item.bName.toLowerCase(),
            type:item.name.toLowerCase(),
            qty:item.quantity
        };
        // Check if item is already in the list
        // Note that the brand name plus item type must match
        var exists = $scope.fridgeItems.filter(function(it) {
            return (it.type === i.type) && (it.brand === i.brand );
          });
        // Item is already in the list, update the number
        if (exists.length){
            // Find the index of the item to be updated
            var itemIndex = $scope.fridgeItems.indexOf( $filter('filter')($scope.fridgeItems, {brand:i.brand, type: i.type}, true)[0] );
            $scope.fridgeItems[itemIndex].qty = item.quantity;
        }else{
        // We're good, add it
            $scope.fridgeItems.push(i);
        }
        $scope.resetForm();
    }

    // Remove item from fridge
    $scope.removeItem = function (index) {
       $scope.fridgeItems.splice(index, 1);
    }

    // Create an arbitrary starting list for the fridge
    var generateFridge = function () {
        $scope.fridgeItems = [
            {
                brand: 'silk',
                type: 'milk',
                qty: 1
            },  
            {
                brand: "land o' lakes",
                type: 'butter',
                qty: 5
            },
            {
                brand: 'walmart',
                type: 'eggs',
                qty: 24
            },
            {
                brand: 'tropicana',
                type: 'orange juice',
                qty: 2
            }
        ];
    }
    generateFridge();
})

// Custom filter to camel case the item names
// Borrowed from https://gist.github.com/umidjons/6668763
.filter('camel', function() {
    return function(input) {
        var ex_input = input.split(' ');
        for (var i = 0, len = ex_input.length; i < len; i++ ){
            ex_input[i] = ex_input[i].charAt( 0 ).toUpperCase() + ex_input[i].slice( 1 );
        }
        return ex_input.join( ' ' );
    }
});
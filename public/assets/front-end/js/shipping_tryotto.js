const ShippingManager = {

    init() {
        let sellerCity = document.querySelector('.origincity').value;
        let totalQuantity = document.getElementById("total_quantity").value;
        let originCity = [];

        $('.origincity').each(function () {
            originCity.push($(this).val());
        });
        
        console.log("originCity:", originCity); // This will log the full array of city values
        
        document.querySelectorAll(".shipping-form").forEach(form => {
            const cityInput = form.querySelector(".city-input");
            const getShippingOptionsBtn = form.querySelector(".get-shipping-options");
            const shippingOptionsContainer = form.querySelector(".shipping-options");
            const proceedToPaymentBtn = form.querySelector(".proceed-to-payment");
    
            // Get the cartGroupId from the shippingOptionsContainer div
            const cartGroupId = shippingOptionsContainer ? shippingOptionsContainer.dataset.cartgroupId : "222";
     
            if (getShippingOptionsBtn) {
                getShippingOptionsBtn.addEventListener("click", () => {
                    this.fetchShippingOptions(cityInput, originCity , shippingOptionsContainer, proceedToPaymentBtn, cartGroupId ,totalQuantity);
                });
            }
        });
    },
    
    async fetchShippingOptions(cityInput,originCity , shippingOptionsContainer, proceedToPaymentBtn, chosenShippingId ,totalQuantity) {
     
    
        const city = cityInput.value.trim();
        if (!city) {
            alert("Please enter a city name.");
            return;
        }
// alert("City: " + city);

        try {
            const requestData = {
                weight: "3",
                originCity: originCity,
                destinationCity: city,
                height: 30,
                width: 30,
                length: 30,
            };

            console.log("Sending request with data:", requestData);

            const response = await fetch("shipping-options", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(requestData),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log("Response data:", data.data.deliveryOptions);

            if (data.success) {
        
                this.renderShippingOptions(data.data.deliveryOptions, shippingOptionsContainer, proceedToPaymentBtn, chosenShippingId , totalQuantity);
            } else {
                throw new Error(data.message || "Failed to get shipping options");
            }
        } catch (error) {
            console.error("Error details:", error);
            alert("Failed to fetch shipping options: " + error.message);
        }
    },
    renderShippingOptions(options, shippingOptionsContainer, proceedToPaymentBtn, chosenShippingId ,totalQuantity) {
        if (!options || options.length === 0) {
            shippingOptionsContainer.innerHTML =
                '<p class="alert alert-info">No shipping options available for this location.</p>';
            return;
        }

        shippingOptionsContainer.innerHTML = `
            <div class="row">
                ${options.map(option => `
                    <div class="shipping-option card mb-3 flex-shrink-0 col-md-6 col-sm-12" data-option-id="${option.deliveryOptionId}">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                ${option.logo ? `<img src="${option.logo}" alt="${option.deliveryCompanyName}" height="40" width="40" class="me-2">` : ""}
                                <h5 class="card-title mb-0 service-name" >${option.deliveryOptionName}</h5>
                            </div>
                            <p class="card-text">
                    <strong>Total Price:</strong> 
                    <span class="price" data-id="${option.deliveryOptionId}">
                    ${option.currency}  ${(option.totalPrice ).toFixed(2)}  <br>
                    
                    </span>
                       
                            </p>
                            <button class="btn btn-primary select-shipping" data-option-id="${option.deliveryOptionId}" data-chosenShipping-id="${chosenShippingId}">
                                Select
                            </button>
                        </div>
                    </div>
                `).join("")}
            </div>
        `;

    

        shippingOptionsContainer.querySelectorAll(".select-shipping").forEach(button => {
            button.addEventListener("click", (event) => {
                this.selectShippingOption(event.target, shippingOptionsContainer, proceedToPaymentBtn ,totalQuantity);
            });
        });
    },

    selectShippingOption(button, shippingOptionsContainer, proceedToPaymentBtn ,totalQuantity) {
        let cartTotalValue = document.getElementById("cartTotalValue");
        let totalAmount = 0; // Initialize totalAmount to 0
        if (cartTotalValue) {
             totalAmount = parseFloat(cartTotalValue.dataset.total.replace(/[^\d.-]/g, '')) || 0;  // Get raw total value from data attribute or fallback to 0 if NaN
        
        
        } else {
            console.log("cartTotalValue not found!");
        }
        
        console.log("Total Amount:", totalAmount);
   
        const chosenShippingId = button.getAttribute("data-chosenShipping-id");
        console.log("Chosen Shipping ID selected:", chosenShippingId);
    
        // Remove 'selected' class from all options
        shippingOptionsContainer.querySelectorAll(".shipping-option").forEach(option => {
            option.classList.remove("selected");
        });
    
        const selectedOption = button.closest(".shipping-option");
        selectedOption.classList.add("selected");
    
        const deliveryOptionId = button.getAttribute("data-option-id");
        const priceElement = selectedOption.querySelector(".price[data-id='" + deliveryOptionId + "']");

        // Get only the numeric value
        let price = priceElement ? parseFloat(priceElement.textContent.replace(/[^\d.-]/g, '')) || 0 : 0;
        const newShippingCostElement = document.querySelector(".new-shipping-cost");

        totalAmount = totalAmount + price ;

    
// Update total text inside the cartTotalValue span
cartTotalValue.textContent = 'SAR' + totalAmount.toFixed(2); // Or use `Math.round(totalAmount)` if you want no decimals

        newShippingCostElement.textContent = `${price}`;
        // Get the service name
        const serviceNameElement = selectedOption.querySelector(".service-name");
        const serviceName = serviceNameElement ? serviceNameElement.textContent.trim() : "";
    
        localStorage.setItem("selectedShippingOption", deliveryOptionId);
        console.log("Selected Delivery Option:", deliveryOptionId, "Price:", price, "Service Name:", serviceName);
    
        if (proceedToPaymentBtn) {
            proceedToPaymentBtn.disabled = false;
        }
    
        // Include service name in the data sent to the server
        const data = {
            option_id: deliveryOptionId,
            price: price,
            service_name: serviceName,  // Added service name
            chosen_shipping_id: chosenShippingId
        };
    
        fetch("/update-shipping-option", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .then(data => {
                console.log("Response from server:", data);
            })
            .catch(error => {
                console.error("Error with AJAX request:", error);
            });
    }
    
};

document.addEventListener("DOMContentLoaded", () => {
    ShippingManager.init();
});

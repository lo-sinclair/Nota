window.onload = function () {

}



const btnUp = {
    el: document.querySelector('.btn-up'),
    show() {
        this.el.classList.remove('btn-up_hide');
    },
    hide() {
        this.el.classList.add('btn-up_hide');
    },
    addEventListener() {
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            scrollY > 400 ? this.show() : this.hide();
        });

        document.querySelector('.btn-up').onclick = () => {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        }
    }
}

btnUp.addEventListener();

// форма обратной связи
const feedbackForm = document.getElementById('feedbackForm');
if(feedbackForm != null) {
    document.getElementById('feedbackForm').addEventListener('submit', function(event)
    {
        event.preventDefault();
        var name = document.getElementById("name").value;
        var telephone = document.getElementById("telephone").value;
        var message = document.getElementById("message").value;
        alert('Сообщение отправлено!');
        this.reset();
    });

}

// добавление товара в корзину
function toNum(str) {
    const num = Number(str.replace(/ /g, ""));
    return num;
}

function toCurrency(num) {
    const format = new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB",
        minimumFractionDigits: 0,
    }).format(num);
    return format;
}

window.baseUrl = window.location.origin;
const cardAddArr = Array.from(document.querySelectorAll(".card_add"));
const cartNum = document.querySelector("#cart_num");
const cart = document.querySelector("#cart");

const popup = document.querySelector(".modal fade");
const popupClose = document.querySelector("#modal_close");
const body = document.body;
const popupContainer = document.querySelector("#modal-content");
const popupProductList = document.querySelector("#product_list");
const popupCost = document.querySelector("#modal_cost");

const orderButton = document.getElementById('make-order');

class Product {
    imageSrc;
    name;
    price;
    pid;
    constructor(custom_card) {
        this.imageSrc = custom_card.querySelector(".card-img").getAttribute('src');
        this.name = custom_card.querySelector(".card_title").innerText;
        this.price = custom_card.querySelector(".card_price").innerText;
        this.pid = custom_card.getAttribute('data-pid');
    }
}

class Cart {
    products;
    constructor() {
        this.products = [];
    }
    get count() {
        return this.products.length;
    }
    addProduct(product) {
        this.products.push(product);
    }
    removeProduct(index) {
        this.products.splice(index, 1);
    }
    get cost() {
        const prices = this.products.map((product) => {
            return toNum(product.price);
        });
        const sum = prices.reduce((acc, num) => {
            return acc + num;
        }, 0);
        return sum;
    }

    set setProductList(list) {
        if (Array.isArray(list)) {
            this.products = list;
        } else {
            this.products = [];
        }
    }
    get productList() {
        return this.products;
    }
}

let myCart = new Cart();

function setCartContent() {
    if (myCart.count > 0) {
        popupProductList.innerHTML = '';
        myCart.products.forEach(function(product) {
            const cartProductEl = `
        <div class="card custom-body-card" >
          <img src="${ product.imageSrc }" class="card-img-top" alt="${ product.imageSrc }"/>
          <div class="card-body">
            <div class="text-section">
              <p class="card-text">${ product.name }</p>
            </div>
            <div class="cta-section">
              <div id="modal_cost">
                ${ product.price }
              </div>
            </div>
            <button class="product-delete" data-product-name="${product.name}" onclick="removeProductFromCartEvent(this)">✖</button>
          </div>
        </div>`;
            popupProductList.insertAdjacentHTML('beforeend', cartProductEl);
        })
    } else {
        popupProductList.innerHTML = '';
        const emptyCartNoteEl = '<p class="empty-cart-note">Корзина пуста, попробуйте что-то добавить</p>';
        popupProductList.insertAdjacentHTML('beforeend', emptyCartNoteEl);
    }
}

function initCart() {
    const savedCart = JSON.parse(localStorage.getItem("cart"));
    if (!savedCart) {
        console.log('savedCart undefined or null. Setting up default value');
        localStorage.setItem("cart", JSON.stringify(myCart.productList));
    } else {
        myCart.setProductList = savedCart;
        setCartContent();
    }
    cartNum.textContent = myCart.count;
}

function clearCart() {
    myCart.setProductList = [];
    localStorage.setItem("cart", JSON.stringify(myCart.productList));
    setCartContent();
    cartNum.textContent = '0';
}

initCart();

const addProductToCartEvent = function (e) {
    e.preventDefault();
    const custom_card = e.target.closest(".custom_card");
    const product = new Product(custom_card);
    myCart.addProduct(product);
    localStorage.setItem("cart", JSON.stringify(myCart.productList));
    cartNum.textContent = myCart.count;
    setCartContent();
};

const removeProductFromCartEvent = function (el) {
    const targetProductName = el.getAttribute('data-product-name');
    const targetProductIndex = myCart.productList.findIndex(product => product.name === targetProductName);
    if (targetProductIndex > -1) {
        myCart.removeProduct(targetProductIndex);
        localStorage.setItem("cart", JSON.stringify(myCart.productList));
        cartNum.textContent = myCart.count;
        setCartContent();
    }
}

const createOrder = async function (el) {
    if (user == undefined) {
        //ToDo - Если юзер не залогенен
        alert('Сначала авторизируйтесь')
    }

    if(myCart.products.length > 0) {
        let productIds = [];
        myCart.products.forEach(function (product) {
           productIds.push(product.pid);
        });
        let csrfToken = document.getElementById('product_list').getAttribute('data-csrf_token');
        let data = {
            'userId': user.id,
            'csrfToken': csrfToken,
            'productIds': productIds
        };

        let response = await fetch(baseUrl + '/api/order/new', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json;charset=utf-8',
            },
            body: JSON.stringify(data)
        });

        let result = await response.json();
        if(response.ok) {
            clearCart();

            //ToDo - Удача
            alert('Заказ успешно оформлен!\nНомер вашего заказа: ' + result.orderId);
        }
        else {
            //ToDo - Ошибка
        }
    }
}

cardAddArr.forEach((cardAdd) => {
    cardAdd.addEventListener("click", addProductToCartEvent);
});

orderButton.addEventListener("click", createOrder);









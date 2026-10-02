// dynamic.js - fetch & render dynamic data from PHP backend
(function () {
  const API_BASE = "backend/php/api/";
  let currentUser = null;

  function fmtPrice(num) {
    const n = parseFloat(num || 0);
    return "Rs " + n.toFixed(2);
  }

  function el(html) {
    const t = document.createElement("template");
    t.innerHTML = html.trim();
    return t.content.firstChild;
  }

  async function getJSON(url) {
    const res = await fetch(url, { credentials: "include" });
    if (!res.ok) throw new Error("Request failed " + res.status);
    return res.json();
  }
  async function postJSON(url, data) {
    const res = await fetch(url, {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error("Request failed " + res.status);
    return res.json();
  }

  // AUTH
  async function loadUser() {
    try {
      const data = await getJSON(API_BASE + "auth_me.php");
      currentUser = data.user || null;
      updateAuthUI();
    } catch (e) {
      currentUser = null;
    }
  }

  function updateAuthUI() {
    const loginLink = document.querySelector('nav .menu a[href="login.html"]');
    if (!loginLink) return;
    if (currentUser) {
      loginLink.textContent = "Logout";
      loginLink.setAttribute("data-logout", "1");
      loginLink.href = "#";
      // Inject admin link if admin
      if (currentUser.is_admin) {
        const ul = loginLink.closest("ul");
        if (ul && !ul.querySelector('a[href="admin/index.html"]')) {
          const li = document.createElement("li");
          const a = document.createElement("a");
          a.href = "admin/index.html";
          a.textContent = "Admin";
          a.className = "hvr-underline-from-center";
          li.appendChild(a);
          ul.insertBefore(li, loginLink.parentElement); // before logout
        }
      }
    } else {
      loginLink.textContent = "Login";
      loginLink.removeAttribute("data-logout");
      loginLink.href = "login.html";
    }
  }

  // RENDERERS
  function renderFoods(list) {
    const container = document.getElementById("foods-grid");
    if (!container) return;
    container.innerHTML = "";
    if (!list.length) {
      container.innerHTML = "<p>No foods found.</p>";
      return;
    }
    list.forEach((f) => {
      const card = el(`
        <div class="food-menu-box">
          <form data-food-id="${f.id}">
            <div class="food-menu-img">
              <img src="${f.image || "img/food/p1.jpg"}" alt="${
        f.name
      }" class="img-responsive img-curve">
            </div>
            <div class="food-menu-desc">
              <h4>${f.name}</h4>
              <p class="food-price">${fmtPrice(f.price)}</p>
              <p class="food-details">${(f.description || "").substring(
                0,
                220
              )}</p>
              <input type="number" name="qty" value="1" min="1">
              <button type="submit" class="btn-primary">Add To Cart</button>
            </div>
          </form>
        </div>`);
      container.appendChild(card);
    });
  }

  function renderCategories(list) {
    const container = document.getElementById("categories-grid");
    if (!container) return;
    container.innerHTML = "";
    list.forEach((c) => {
      const item = el(`
        <a href="category-foods.html?category_id=${c.id}">
          <div class="float-container">
            <img src="img/category/${c.slug}.jpg" class="img-responsive" alt="${c.name}">
            <h3 class="float-text text-white">${c.name}</h3>
          </div>
        </a>`);
      container.appendChild(item);
    });
  }

  function renderCart(cart) {
    const tbody = document.getElementById("cart-items");
    if (!tbody) return;
    tbody.innerHTML = "";
    let total = 0;
    (cart.items || []).forEach((it) => {
      const lineTotal = it.price * it.qty;
      total += lineTotal;
      const row = el(`
        <tr data-food-id="${it.food_id}">
          <td><img src="${it.image || "img/food/p1.jpg"}" alt="${
        it.name
      }" style="width:40px"></td>
          <td>${it.name}</td>
            <td>${fmtPrice(it.price)}</td>
          <td><input type="number" class="cart-qty" value="${
            it.qty
          }" min="1" style="width:55px"></td>
          <td>${fmtPrice(lineTotal)}</td>
          <td><a href="#" class="btn-delete" data-remove="${
            it.food_id
          }">&times;</a></td>
        </tr>`);
      tbody.appendChild(row);
    });
    const totalEl = document.getElementById("cart-total-amount");
    if (totalEl) totalEl.textContent = fmtPrice(total);
  }

  function renderOrderCart(cart) {
    const tbody = document.getElementById("order-items");
    if (!tbody) return;
    tbody.innerHTML = "";
    let total = 0,
      i = 0;
    (cart.items || []).forEach((it) => {
      const lineTotal = it.price * it.qty;
      total += lineTotal;
      i++;
      const row = el(`<tr data-food-id="${it.food_id}">
          <td>${i}</td>
          <td><img src="${
            it.image || "img/food/p1.jpg"
          }" style="width:40px" alt="${it.name}"></td>
          <td>${it.name}</td>
          <td>${fmtPrice(it.price)}</td>
          <td><input type="number" class="cart-qty" value="${
            it.qty
          }" min="1" style="width:55px"></td>
          <td>${fmtPrice(lineTotal)}</td>
          <td><a href="#" class="btn-delete" data-remove="${
            it.food_id
          }">&times;</a></td>
        </tr>`);
      tbody.appendChild(row);
    });
    const totalEl = document.getElementById("order-total-amount");
    if (totalEl) totalEl.textContent = fmtPrice(total);
  }

  // LOADERS
  async function loadFoods() {
    try {
      const params = new URLSearchParams(location.search);
      if (params.get("category_id")) {
        const catId = params.get("category_id");
        const data = await getJSON(
          API_BASE + `foods_by_category.php?category_id=${catId}`
        );
        renderFoods(data.foods || []);
        const heading = document.getElementById("category-heading");
        if (heading) {
          const name =
            data.category && data.category.name
              ? data.category.name
              : "Category";
          heading.innerHTML = `Foods in <span class="text-white">${name}</span>`;
        }
      } else if (params.get("q")) {
        const q = params.get("q");
        const data = await getJSON(
          API_BASE + `foods_search.php?q=${encodeURIComponent(q)}`
        );
        renderFoods(data.foods || []);
      } else {
        const data = await getJSON(API_BASE + "foods_list.php");
        renderFoods(data.foods || []);
      }
    } catch (e) {
      console.error(e);
      const c = document.getElementById("foods-grid");
      if (c) c.innerHTML = "<p>Error loading foods.</p>";
    }
  }

  async function loadCategories() {
    try {
      const data = await getJSON(API_BASE + "categories_list.php");
      renderCategories(data.categories || []);
    } catch (e) {
      console.error(e);
    }
  }

  async function loadCart() {
    try {
      const data = await getJSON(API_BASE + "cart_list.php");
      renderCart(data);
      renderOrderCart(data);
    } catch (e) {
      console.error(e);
    }
  }

  async function addToCart(foodId, qty) {
    try {
      await postJSON(API_BASE + "cart_add.php", { food_id: foodId, qty });
      loadCart();
    } catch (e) {
      alert("Failed to add to cart");
    }
  }
  async function updateCart(foodId, qty) {
    try {
      await postJSON(API_BASE + "cart_update.php", { food_id: foodId, qty });
      loadCart();
    } catch (e) {
      console.error(e);
    }
  }
  async function removeFromCart(foodId) {
    try {
      await postJSON(API_BASE + "cart_remove.php", { food_id: foodId });
      loadCart();
    } catch (e) {
      console.error(e);
    }
  }

  function bindEvents() {
    document.addEventListener("submit", function (e) {
      const form = e.target.closest("form[data-food-id]");
      if (form) {
        e.preventDefault();
        const foodId = form.getAttribute("data-food-id");
        const qty = form.querySelector('input[name="qty"]')?.value || 1;
        addToCart(foodId, qty);
      }
    });
    document.addEventListener("change", function (e) {
      if (e.target.classList.contains("cart-qty")) {
        const tr = e.target.closest("tr[data-food-id]");
        const id = tr.getAttribute("data-food-id");
        const qty = e.target.value;
        updateCart(id, qty);
      }
    });
    document.addEventListener("click", function (e) {
      if (e.target.matches("[data-remove]")) {
        e.preventDefault();
        removeFromCart(e.target.getAttribute("data-remove"));
      }
      if (e.target.matches("[data-logout]")) {
        e.preventDefault();
        postJSON(API_BASE + "auth_logout.php", {}).then(() => {
          currentUser = null;
          updateAuthUI();
          loadCart();
        });
      }
    });
    // Intercept search form to use dynamic search page
    const searchForms = document.querySelectorAll(
      'form[action="food-search.html"], form[action=""], form[action="#"]'
    );
    searchForms.forEach((f) => {
      if (f.querySelector('input[type="search"]')) {
        f.addEventListener("submit", (ev) => {
          const q = f.querySelector('input[type="search"]').value.trim();
          if (q) {
            ev.preventDefault();
            window.location.href =
              "food-search.html?q=" + encodeURIComponent(q);
          }
        });
      }
    });
  }

  function init() {
    bindEvents();
    loadUser();
    if (document.getElementById("foods-grid")) loadFoods();
    if (document.getElementById("categories-grid")) loadCategories();
    if (document.getElementById("cart-items")) loadCart();
    if (document.getElementById("order-items")) loadCart();
    setupLoginForm();
    setupCheckout();
  }

  function setupLoginForm() {
    const form = document.getElementById("login-form");
    if (!form) return;
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const email = form.querySelector("input[name=email]").value.trim();
      const password = form.querySelector("input[name=password]").value;
      const status = document.getElementById("login-status");
      status.textContent = "Logging in...";
      postJSON(API_BASE + "auth_login.php", { email, password })
        .then((r) => {
          status.textContent = "Success";
          window.location = "index.html";
        })
        .catch((err) => {
          status.textContent = "Login failed";
        });
    });
  }

  function setupCheckout() {
    const form = document.getElementById("checkout-form");
    if (!form) return;
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const status = document.getElementById("checkout-status");
      if (!currentUser) {
        status.textContent = "Please login first";
        window.location = "login.html";
        return;
      }
      status.textContent = "Placing order...";
      postJSON(API_BASE + "order_checkout.php", {})
        .then((r) => {
          status.textContent = "Order placed #" + r.order_id;
          loadCart();
        })
        .catch((err) => {
          status.textContent = "Checkout failed";
        });
    });
  }

  document.addEventListener("DOMContentLoaded", init);
})();

import { createContext, useContext, useState } from "react";

const CartContext = createContext();

export const CartProvider = ({ children, initialCart, csrf }) => {
  const [cart, setCart] = useState(initialCart);
  const [showCart, setShowCart] = useState(false);

  const toggleCart = () => {
    setShowCart(!showCart)
  }

  const addToCart = async (product) => {
    const data = { ...product, _token: csrf };
    const response = await fetch("/cart/add", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    const cart = await response.json();
    if (cart.error) {
      alert(cart.error);
    } else {
      setCart(cart);
    }
  };

  const removeFromCart = async (rowId) => {
    const response = await fetch("/cart/remove", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ rowId, _token: csrf }),
    });
    if (response.ok) {
      const cart = await response.json();
      setCart(cart);
    }
  };

  const emptyCart = async () => {
    const response = await fetch("/cart/destroy", {
      method: "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
    const cart = await response.json();
    if (cart.error) {
      alert(cart.error);
    } else {
      setCart(cart);
    }
  };

  const applyCoupon = async (code) => {
    const response = await fetch("/cart/code", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ code, _token: csrf }),
    });
    const cart = await response.json();
    if (cart.error) {
      alert(cart.error);
    } else {
      setCart(cart);
    }
  }

  const items = cart ? Object.entries(cart.items) : [];

  const countTickets = () => {
    if (!items) return 0;
    return items.reduce((t, item) => t + (item.qty??0), 0);
  }

  return (
    <CartContext.Provider
      value={{
        items,
        total: cart?.total ?? 0,
        count: countTickets(),
        showCart,
        setShowCart,
        toggleCart,
        setCart,
        addToCart,
        removeFromCart,
        emptyCart,
        applyCoupon
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => {
  return useContext(CartContext);
};

import { useCart } from "@/contexts/CartContext";
import { router } from "@inertiajs/react";
import styles from "./Cart.module.scss";
import classNames from "classnames";
import { Button } from "@/components/ui";

const Cart = () => {
  const { items, total, showCart, removeFromCart, emptyCart, setShowCart } = useCart();
  const classes = classNames(styles.cart, {
    [styles.visible]: showCart,
  });

  const handleCheckout = () => {
    setShowCart(false)
    router.get('/confirmacio')
  }

  return (
    <div className={classes}>
      <p>Contingut del cistell:</p>
      {items &&
        items.map(([id, item]) => (
          <div className={styles.item} key={id}>
            {item.name} ({item.options.rate}
            {item.options.seat && (
              <span>
                {item.options.seat.s} / {item.options.seat.f}
              </span>
            )}{" "}
            x {item.qty}: {item.subtotal} €
            <button onClick={() => removeFromCart(id)}>Elimina</button>
          </div>
        ))}
      <p>Total: {total} €</p>
      <p>
        <Button onClick={emptyCart} block={true} outline={true}>
          Buida el cistell
        </Button>
      </p>
      <p>
        <Button onClick={handleCheckout} block={true} disabled={!items.length}>
          Finalitza la comanda
        </Button>
      </p>
      <p>
        <Button onClick={()=>setShowCart(false)} block={true} link={true}>
          Continua comprant
        </Button>
      </p>
    </div>
  );
};

export default Cart;

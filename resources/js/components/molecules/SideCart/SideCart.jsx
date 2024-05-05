import { useCart } from "@/contexts/CartContext";
import { router } from "@inertiajs/react";
import styles from "./SideCart.module.scss";
import classNames from "classnames";
import { Button, Flex } from "@/components/atoms";
import { dayFormatted } from "@/utils/date";

const Cart = () => {
  const { items, total, showCart, removeFromCart, emptyCart, setShowCart } =
    useCart();
  const classes = classNames(styles.cart, {
    [styles.visible]: showCart,
  });

  const handleCheckout = () => {
    setShowCart(false);
    router.get("/confirmacio");
  };

  return (
    <div className={classes}>
      {items &&
        items.map(([id, item]) => (
          <Flex gap={2} key={id} spacerBottom={3}>
            <div className={styles.itemImg}>
              <img
                src={
                  "https://source.unsplash.com/random/200x20" + (item.id % 9)
                }
                alt={item.name}
              />
              <span className={styles.itemQty}>{item.qty}</span>
            </div>
            <div>
              <h4 className={styles.itemName}>
                <strong>{item.name}</strong> {item.options.rate}
              </h4>
              <div className={styles.itemInfo}>
                {item.options.seat ? (
                  <span>
                    {item.options.seat.s} / {item.options.seat.f}
                  </span>
                ) : (
                  <span>
                    {dayFormatted(item.options.day)}
                    <br />
                    {item.options.hour}
                  </span>
                )}
                <button onClick={() => removeFromCart(id)}>Elimina</button>
              </div>
            </div>
            <div className={styles.itemSubtotal}>{item.subtotal} €</div>
          </Flex>
        ))}
      {items.length ? (
        <>
          <p>Total: {total} €</p>
          <Flex gap={1} flexDirection="column">
            <Button onClick={emptyCart} block={true} outline={true}>
              Buida el cistell
            </Button>
            <Button
              onClick={handleCheckout}
              block={true}
              disabled={!items.length}
            >
              Finalitza la comanda
            </Button>
            <Button onClick={() => setShowCart(false)} block={true} link={true}>
              Continua comprant
            </Button>
          </Flex>
        </>
      ) : (
        <>
          <p className="text-center">El cistell és buit</p>
          <Button onClick={() => setShowCart(false)} block={true} link={true}>
            Continua comprant
          </Button>
        </>
      )}
    </div>
  );
};

export default Cart;

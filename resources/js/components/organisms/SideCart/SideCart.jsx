import { useCart } from "@/contexts/CartContext";
import { router } from "@inertiajs/react";
import styles from "./SideCart.module.scss";
import classNames from "classnames";
import { Button, Flex } from "@/components/atoms";
import { CartItem } from "@/components/molecules";

const SideCart = () => {
  const { items, total, showCart, removeFromCart, emptyCart, setShowCart } =
    useCart();
  const classes = classNames(styles.cart, {
    [styles.visible]: showCart,
  });

  const handleCheckout = () => {
    setShowCart(false);
    router.get("/confirmacio");
  };

  console.log(items);

  return (
    <div className={classes}>
      {items &&
        items.map(([id, item]) => (
          <CartItem item={item} key={id} onRemove={removeFromCart} />
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

export default SideCart;

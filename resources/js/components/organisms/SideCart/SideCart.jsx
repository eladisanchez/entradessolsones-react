import { useCart } from "@/contexts/CartContext";
import { router } from "@inertiajs/react";
import styles from "./SideCart.module.scss";
import classNames from "classnames";
import { Button, Flex, TextFormat, Spacer, Icon } from "@/components/atoms";
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

  return (
    <div className={classes}>
      <Icon
        icon="close"
        className={styles.close}
        onClick={() => setShowCart(false)}
      />
      {items &&
        items.map(([id, item]) => (
          <CartItem item={item} key={id} onRemove={removeFromCart} />
        ))}
      {items.length ? (
        <>
          <Spacer bottom={3}>
            <TextFormat textAlign="right">Total: {total} €</TextFormat>
          </Spacer>
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
          </Flex>
        </>
      ) : (
        <Spacer bottom={3}>
          <TextFormat color="faded" textAlign="center">
            El cistell és buit
          </TextFormat>
        </Spacer>
      )}
      <Spacer top={2}>
        <Button onClick={() => setShowCart(false)} block={true} link={true}>
          Continua comprant
        </Button>
      </Spacer>
    </div>
  );
};

export default SideCart;

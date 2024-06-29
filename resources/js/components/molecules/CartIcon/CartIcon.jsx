import { Icon, Badge } from "@/components/atoms";
import { useCart } from "@/contexts/CartContext";
import styles from "./CartIcon.module.scss";

const CartIcon = () => {
  const { toggleCart, count } = useCart();
  return (
    <button
      aria-label="Cistell de la compra"
      onClick={() => toggleCart()}
      className={styles.cartIcon}
    >
      <Icon icon="cart" />
      {!!count && <Badge>{count}</Badge>}
    </button>
  );
};

export default CartIcon;

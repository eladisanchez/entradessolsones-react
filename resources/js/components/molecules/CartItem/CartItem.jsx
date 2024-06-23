import { Flex } from "@/components/atoms";
import styles from "./CartItem.module.scss";
import { dayFormatted } from "@/utils/date";

const CartItem = ({ item, onRemove, ...props }) => {
  return (
    <Flex gap={2} spacerBottom={3} {...props}>
      <div className={styles.itemImg}>
        <img src={"/image/" + item.options.image} alt={item.name} />
        <span className={styles.itemQty}>{item.qty}</span>
      </div>
      <div className={styles.itemContent}>
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
          <button onClick={() => onRemove(item.rowId)} className={styles.delete} aria-label="Elimina">
            Elimina
          </button>
        </div>
      </div>
      <div className={styles.itemSubtotal}>{item.subtotal} €</div>
    </Flex>
  );
};

export default CartItem;

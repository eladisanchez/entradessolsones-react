import classNames from "classnames";
import styles from "./Modal.module.scss";
import { Icon } from "@/components/atoms";

const Modal = ({ children, isOpen, onClose, width = 500 }) => {
  const classes = classNames(styles.modal, {
    [styles.open]: isOpen,
  });

  return (
    <div className={classes}>
      <div className={styles.content} style={{ maxWidth: width + "px" }}>
        {children}
        <Icon icon="close" onClick={onClose} className={styles.close} />
      </div>
      <div className={styles.overlay} onClick={onClose}></div>
    </div>
  );
};

export default Modal;

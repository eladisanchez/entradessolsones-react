import classNames from "classnames";
import styles from "./Modal.module.scss";

const Modal = ({ children, isOpen, onClose }) => {
  const classes = classNames(styles.modal, {
    [styles.open]: isOpen,
  });

  return (
    <div className={classes}>
      <div className={styles.overlay} onClick={onClose}></div>
      <div className={styles.content}>{children}</div>
    </div>
  );
};

export default Modal;

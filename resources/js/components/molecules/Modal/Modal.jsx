import classNames from "classnames";
import styles from "./Modal.module.scss";

const Modal = ({ children, isOpen, onClose, width = 500 }) => {
  const classes = classNames(styles.modal, {
    [styles.open]: isOpen,
  });

  return (
    <div className={classes}>
      <div className={styles.content} style={{ width: width + "px" }}>
        {children}
      </div>
      <div
        className={styles.overlay}
        onClick={() => {
          onClose();
          console.log("casdc");
        }}
      ></div>
    </div>
  );
};

export default Modal;

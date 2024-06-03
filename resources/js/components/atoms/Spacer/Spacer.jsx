import styles from "./Spacer.module.scss";

const Spacer = ({ size }) => {
  return <div className={styles[`spacer-${size}`]} />;
};

export default Spacer;

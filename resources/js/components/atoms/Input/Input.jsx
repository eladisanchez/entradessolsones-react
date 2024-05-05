import { useId } from "react";
import styles from "./Input.module.scss";

const Input = ({ label, name, value, ...props }) => {
  const id = useId();
  return (
    <div className={styles.input}>
      <label htmlFor={id + "-" + name}>{label}</label>
      <input id={id + "-" + name} name={name} value={value} {...props} />
    </div>
  );
};

export default Input;

import { useId } from "react";
import styles from "./Input.module.scss";
import classNames from "classnames";
import { useState } from "react";

const Input = ({ label, name, value, ...props }) => {
  const id = useId();
  const [focused, setFocused] = useState(false);
  const handleFocus = (e) => {
    setFocused(e.type === "focus");
  };
  return (
    <div
      className={classNames(styles.input, props.className, {
        [styles.focused]: focused,
      })}
    >
      {label && <label htmlFor={id + "-" + name}>{label}</label>}
      {props.type === "textarea" ? (
        <textarea
          id={id + "-" + name}
          name={name}
          value={value}
          {...props}
          onFocus={handleFocus}
          onBlur={handleFocus}
          onChange={props.onChange}
        />
      ) : (
        <input
          id={id + "-" + name}
          name={name}
          value={value}
          {...props}
          onFocus={handleFocus}
          onBlur={handleFocus}
          onChange={props.onChange}
        />
      )}
    </div>
  );
};

export default Input;

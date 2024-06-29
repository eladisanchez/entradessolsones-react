import styles from './Alert.module.scss';

const Alert = ({ children }) => {
  return <div className={styles.alert}>{children}</div>;
};

export default Alert;
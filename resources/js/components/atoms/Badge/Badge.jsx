import styles from './Badge.module.scss';
import classNames from 'classnames';

const Badge = ({ children, className, ...props }) => {
  return (
    <span className={classNames(styles.badge, className)} {...props}>
      {children}
    </span>
  );
};

export default Badge;

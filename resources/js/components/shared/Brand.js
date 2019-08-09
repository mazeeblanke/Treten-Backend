import React from 'react';

const Brand = (props) => {
  return (
    <a href="/">
      <div className="is-flex">
        <img className="brand__logo" src="/t/images/logo.png" />
        <h4 className={["brand__text", props.isWhite ? "is-white" : "" ].join(' ')}>Treten Academy</h4>
      </div>
    </a>
  );
};

export default Brand;

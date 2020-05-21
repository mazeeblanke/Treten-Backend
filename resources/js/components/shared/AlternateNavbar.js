import React from 'react';
import Brand from './Brand';
import queryString from 'query-string';
import {
  Navbar,
} from 'reactstrap';

const AlternateNavbar = () => {
  return (
    <Navbar className="no-box-shadow light-border-bottom" color="faded" light expand="md">
      <div className="container d-flex justify-content-center">
        <a
            style={{ position: 'absolute', left: '10%' }}
            href={(queryString.parse(location.search) || {redirect: '/'}).redirect}
        >
          Back
        </a>
        <Brand />
      </div>
    </Navbar>
  );
};

export default AlternateNavbar;

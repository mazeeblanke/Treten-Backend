import React from 'react';
import Link from 'next/link';
import { connect } from 'react-redux';
import { Layout, Menu, Icon, Button } from 'antd';
const { Header } = Layout;
const { SubMenu } = Menu;
import { Input } from 'antd';
const { Search } = Input;
import Brand from './Brand';

import 'bootstrap/dist/css/bootstrap.min.css';

import {
  Collapse,
  Navbar,
  NavbarBrand,
  NavbarToggler,
  Nav,
  NavItem,
  NavLink,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem } from 'reactstrap';


class Navigation extends React.Component {

  constructor(props) {
    super(props);

    this.toggle = this.toggle.bind(this);
    this.state = {
      isOpen: false
    };
  }
  toggle() {
    this.setState({
      isOpen: !this.state.isOpen
    });
  }

  render () {
    return (

      <Navbar className="fixed-top" style={this.props.noBoxShadow ? { boxShadow: 'none', border: '1px solid #F0F1F3' } : {} } color="faded" light expand="md">
        <div className="container">
          <NavbarBrand href="/">
            {/* <img className="brand__logo" src="/static/images/logo.png" />
            <h4 className="brand__text">Treten Academy</h4> */}
            <Brand />
          </NavbarBrand>

          <NavbarToggler onClick={this.toggle} />
          <Collapse isOpen={this.state.isOpen} navbar>
            <Nav className="mr-auto">
              <div className="is-flex is-vcentered">
                <Search
                  size="large"
                  placeholder="What do you want to learn?"
                  onSearch={value => console.log(value)}
                  style={{ width: 250 }}
                />
              </div>
            </Nav>
            <Nav className="ml-auto" navbar>
              <UncontrolledDropdown nav inNavbar>
                <DropdownToggle nav>
                  Courses
                  <Icon type="down" />
                </DropdownToggle>
                <DropdownMenu right>
                  <DropdownItem>
                    Option 1
                  </DropdownItem>
                  <DropdownItem>
                    Option 2
                  </DropdownItem>
                  <DropdownItem divider />
                  <DropdownItem>
                    Reset
                  </DropdownItem>
                </DropdownMenu>
              </UncontrolledDropdown>
              <NavItem>
                <Link href="/about-us/">About us</Link>
              </NavItem>
              <UncontrolledDropdown nav inNavbar>
                <DropdownToggle nav>
                  Why us
                  <Icon type="down" />
                </DropdownToggle>
                <DropdownMenu right>
                  <DropdownItem>
                    Option 1
                  </DropdownItem>
                  <DropdownItem>
                    Option 2
                  </DropdownItem>
                  <DropdownItem divider />
                  <DropdownItem>
                    Reset
                  </DropdownItem>
                </DropdownMenu>
              </UncontrolledDropdown>
              <NavItem>
                <Link href="/blog">
                  <a>Blog</a>
                </Link>
              </NavItem>
              <NavItem>
                <Link href="/contact-us">
                  <a>Contact</a>
                </Link>
              </NavItem>
              <NavItem>
                <Link href="/auth">
                  <Button className="ml-3" size="large" type="danger">Register/Log in</Button>
                </Link>
              </NavItem>
            </Nav>
          </Collapse>
          </div>
        </Navbar>


      // <Header className="header is-flex">
      //   <div className="header__left is-flex">
      //     <div className="brand is-flex is-vcentered">
      //       <img className="brand__logo" src="/static/images/logo.png" />
      //       <h4 className="brand__text">Treten Academy</h4>
      //     </div>
      //     <div className="is-flex is-vcentered">
      //       <Search
      //         size="large"
      //         placeholder="What do you want to learn?"
      //         onSearch={value => console.log(value)}
      //         style={{ width: 300 }}
      //       />
      //     </div>
      //   </div>


      //   <Menu
      //     mode="horizontal"
      //     // defaultSelectedKeys={['2']}
      //     className="header__right"
      //   >

      //     <SubMenu
      //       key="courses"
      //       title={
      //         <span>
      //           Courses
      //           <Icon type="down" />
      //         </span>
      //       }
      //     >
      //       <Menu.Item key="1">option1</Menu.Item>
      //       <Menu.Item key="2">option2</Menu.Item>
      //       <Menu.Item key="3">option3</Menu.Item>
      //       <Menu.Item key="4">option4</Menu.Item>
      //     </SubMenu>
      //     <Menu.Item key="1">About us</Menu.Item>
      //     <Menu.Item key="3">Why us</Menu.Item>
      //     <SubMenu
      //       key="resources"
      //       title={
      //         <span>
      //           Resources
      //           <Icon type="down" />
      //         </span>
      //       }
      //     >
      //       <Menu.Item key="1">option1</Menu.Item>
      //       <Menu.Item key="2">option2</Menu.Item>
      //       <Menu.Item key="3">option3</Menu.Item>
      //       <Menu.Item key="4">option4</Menu.Item>
      //     </SubMenu>
      //     <Menu.Item key="4">Blog</Menu.Item>
      //     <Menu.Item key="5">Contact</Menu.Item>
      //     <Menu.Item key="6">
      //       <Button size="large" type="danger">Register/Log in</Button>
      //     </Menu.Item>
      //   </Menu>
      // </Header>
    );
  }
}

const mapStateToProps = (state) => ({
  // isLoggedIn: state.auth.login.isLoggedIn
})

export default connect(mapStateToProps)(Navigation);

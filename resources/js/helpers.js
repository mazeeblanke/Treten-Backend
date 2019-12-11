export const isValidPhoneNumber = (phoneNumber) => {
  const phoneUtil = require('google-libphonenumber').PhoneNumberUtil.getInstance()
  try {
    const number = phoneUtil.parseAndKeepRawInput(phoneNumber)
    return phoneUtil.isValidNumber(number)
  } catch (err) {
    return false
  }
}

import axios from 'axios'
export default () => {
  return axios.create({
    baseURL: process.env.APP_URL
  })
}
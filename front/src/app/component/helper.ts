export function getAge(birthday: string): number {
  const [year, month, day] = birthday.split('-').map(Number)
  const today = new Date()
  const birthDate = new Date(year, month - 1, day)

  const ageInMilliseconds = today.getTime() - birthDate.getTime()
  const ageDate = new Date(ageInMilliseconds)
  return Math.abs(ageDate.getUTCFullYear() - 1970)
}

export function getDate(date: string): string {
  const [year, month, day] = date.split('-').map(Number)
  const dateFormat = new Date(year, month - 1, day)

  return dateFormat.toLocaleDateString("fr-FR")
}

export function getTime(dateString: string, hoursString: string): string {
  let date = new Date(`${dateString}T${hoursString}`)
  let currentDate = new Date()
  let timePassed = (currentDate.getTime() - date.getTime()) / 1000
  let year = 60 * 60 * 24 * 30 * 365
  let month = 60 * 60 * 24 * 30
  let day = 60 * 60 * 24
  let hour = 60 * 60
  let minute = 60

  if (timePassed >= year) {
    return `${Math.floor(timePassed / year)}ans`;
  } else if (timePassed >= month) {
    return `${Math.floor(timePassed / month)}m`;
  } else if (timePassed >= day) {
    return `${Math.floor(timePassed / day)}j`;
  } else if (timePassed >= hour) {
    return `${Math.floor(timePassed / hour)}h`;
  } else if (timePassed >= minute) {
    return `${Math.floor(timePassed / minute)}m`;
  }

  return "now"
}

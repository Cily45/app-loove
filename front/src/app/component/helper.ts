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
